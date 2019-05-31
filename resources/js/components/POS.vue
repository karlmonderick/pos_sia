<template>
    <div class="container-fluid">
        <div class="row mt-5" v-if="$gate.isAdmin()">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Client Information</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModalClient">Add New Client<i class="fas fa-user-plus fa-fw"></i></button>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <form @submit.prevent="findClient()">
                            <table class="table ">
                                <tr>
                                    <th>Choose Client</th>
                                    <th>
                                        <select v-model="search_client.client_id" class="form-control">
                                            <option value="" selected>--Select--</option>
                                            <option v-for="client_list in clients" :key="client_list.id" :value="client_list.id" >{{client_list.first_name}} {{client_list.last_name}}</option>
                                        </select>
                                    </th>
                                    <th><button type="submit" class="btn btn-light"><i class="fas fa-search"></i></button></th>
                                </tr>
                            </table>
                        </form>

                        <table class="table table-hover table-bordered">
                            <tbody>
                                <tr>
                                    <td>Name: </td>
                                    <td>{{chosen_client.first_name}} {{chosen_client.middle_name}} {{chosen_client.last_name}}</td>
                                </tr>
                                <tr>
                                    <td>Address: </td>
                                    <td>{{chosen_client.address}}</td>
                                </tr>
                                <tr>
                                    <td>Contact Number: </td>
                                    <td>{{chosen_client.contact}}</td>
                                </tr>
                                <tr>
                                    <td>Email Address: </td>
                                    <td>{{chosen_client.email}} <p v-show="!chosen_client.email">None</p> </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                    </div>
                </div>



                <!-- CART -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Cart ({{ carts.length }}):</h3>

                    </div>
                    <!-- /.card-header -->

                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover table-bordered">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Qty.</th>
                                    <th>Amount</th>
                                    <th>Remove</th>
                                </tr>

                                <tr v-if="Object.keys(carts).length === 0">
                                    <td colspan="5" align="center">-- No item selected --</td>
                                </tr>

                                <tr v-for="(cart, n) in carts" :key="cart.id">
                                    <td>{{cart.name}}</td>
                                    <td>Php. {{cart.price}}</td>
                                    <td style="width: 200px"> <input type="number" readonly class="form-control col-5"  v-model="cart.qty"></td>
                                    <td>{{cart.amount}}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" @click="removeCart(n)"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="3" align="right"><b>Total Price</b></td>
                                    <td colspan="2">Php. {{totalprice}}</td>
                                </tr>

                                <tr>
                                    <td>
                                        <!-- <a href="/checkout" class="btn btn-primary">Checkout</a> -->
                                        <button @click="createCart()" class="btn btn-primary">Checkout</button>
                                        <button @click="clearCart()" class="btn btn-dark">Clear</button>
                                    </td>
                                </tr>
                            </tbody></table>
                        </div>
                    <!-- /.card-body -->
                </div>
                <!-- /CART -->


            <!-- /.card -->
          </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Product Lists</h3>

                        <div class="card-tools">

                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Add</th>
                            </tr>

                            <tr v-for="product in products.data" :key="product.id">
                                <td>{{product.name}}</td>
                                <td>{{product.price}}</td>

                                <td>
                                    <div class="btn-group">
                                        <input type="number" v-model="quantity" style="width: 60px">
                                        <button class="btn btn-sm btn-primary" @click="addCart(product)">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>

                                </td>
                            </tr>
                        </tbody></table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="products" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div class="row justify-content-center" v-else>
            <not-found></not-found>
        </div>

         <!-- Modal for Client Adding-->
        <div class="modal fade" id="addNewClient" tabindex="-1" role="dialog" aria-labelledby="addNewClient" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New Client Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createClient()">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="first_Name">First Name</label>
                                    <input v-model="form.first_name" type="text" class="form-control" id="first_name" placeholder="Enter First Name" :class="{ 'is-invalid': form.errors.has('first_name') }">
                                    <has-error :form="form" field="first_name"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="middle_Name">Middle Name</label>
                                    <input v-model="form.middle_name" type="text" class="form-control" id="middle_name" placeholder="Enter Middle Name" :class="{ 'is-invalid': form.errors.has('middle_name') }">
                                    <has-error :form="form" field="middle_name"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="last_Name">Last Name</label>
                                    <input v-model="form.last_name" type="text" class="form-control" id="last_name" placeholder="Enter Last Name" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                    <has-error :form="form" field="last_name"></has-error>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea v-model="form.address" name="address" id="address" placeholder="Enter Address" cols="30" rows="10" class="form-control" :class="{ 'is-invalid': form.errors.has('address') }"></textarea>
                                    <has-error :form="form" field="address"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="contact">Contact Number</label>
                                    <input v-model="form.contact" type="text" class="form-control" id="contact" placeholder="Enter Contact Number. Pls add (+63)" :class="{ 'is-invalid': form.errors.has('contact') }">
                                    <has-error :form="form" field="contact"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Enter email Number" :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- Modal END -->

    </div>


</template>

<script>
    export default {
        data() {
            return {
                editmode: false,
                products : {},
                clients : {},
                chosen_client: {},
                form: new Form({
                    id:'',
                    first_name: '',
                    middle_name: '',
                    last_name: '',
                    email: '',
                    contact: '',
                    address: '',
                }),
                search_client: new Form({
                    client_id: '',
                }),
                carts: [],
                cartadd: {
                    id:'',
                    product_name:'',
                    price:'',
                    qty: '',
                    amount:'',

                },
                badge: '0',
                quantity: '1',
                totalprice: '0',
                checkOut: new Form({
                    client_id: '',
                    cart_content: '',
                    total_amount: '',
                    branch_id:'',

                }),
            }
        },
        methods: {
            getResults(page = 1){
                axios.get('api/product?page=' + page)
                .then(response => {
                    this.products = response.data;
                });
            },

            findClient(){
                axios.get('api/searchClient?q=' +this.search_client.client_id)
                .then((data)=>{
                    this.chosen_client = data.data
                })
                .catch(()=>{
                    swal.fire(
                        'Error!',
                        'Cannot find the client. Please check if they have records.',
                        'warning'
                    )
                })
            },

            newModalClient(){
                this.editmode = false;
                this.form.reset();
                $('#addNewClient').modal('show');
            },

            loadProducts(){
                if(this.$gate.isAdmin()){
                    axios.get("api/product").then(({data}) => (this.products = data));
                }

            },
            loadClient(){
                if(this.$gate.isAdmin()){
                    axios.get("api/client").then(({data}) => (this.clients = data));
                }

            },
            createClient(){
                this.$Progress.start();

                this.form.post('api/client')
                .then(()=>{
                    Fire.$emit('AfterCreatedClient');
                    $('#addNewClient').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Client Registered Successfully!'
                    })

                    this.$Progress.finish();

                })
                .catch(()=>{
                    toast.fire({
                        type: 'error',
                        title: 'Error, Check your Inputs! Failed to register Client Info.'
                    })
                    this.$Progress.fail();
                })


            },

            viewCart(){
                if(localStorage.getItem('carts')){
                    this.carts = JSON.parse(localStorage.getItem('carts'));
                    this.badge = this.carts.length;
                    this.totalprice = this.carts.reduce((total, item)=>{
                        return total + item.qty * item.price;
                    } ,0);
                }
            },

            addCart(pro){

                // if product is already existing in the cart
                for(var item in this.carts){
                    if(this.carts[item].id === pro.id){
                        this.carts[item].qty ++;
                        this.carts[item].amount = pro.price * this.carts[item].qty;
                        this.storeCart();
                        return;
                    }
                }

                // if product is not existing in the cart
                this.cartadd.id = pro.id;
                this.cartadd.name = pro.name;
                this.cartadd.price = pro.price;
                this.cartadd.qty = this.quantity;
                this.cartadd.amount = pro.price * this.quantity;
                this.carts.push(this.cartadd);
                this.cartadd = {};
                this.storeCart();
            },

            removeCart(pro){
                this.carts.splice(pro, 1);
                this.storeCart();
            },

            clearCart(){
                this.carts.splice(0, this.carts.length);
                this.storeCart();
            },

            storeCart(){
                let parsed = JSON.stringify(this.carts);
                localStorage.setItem('carts', parsed);
                this.viewCart();
            },

            createCart(){
                this.$Progress.start();
                console.log(this.checkOut);


                swal.fire({
                    title: 'Please check info before continuing.',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continue Check Out!'
                    }).then((result) => {
                    if (result.value) {

                        if(this.chosen_client.id == null){
                            swal.fire(
                                'Ooops!',
                                'There are no clients selected. Please',
                                'info'
                            )
                            this.$Progress.fail();
                        }
                        else if(this.carts.length < 1){
                            swal.fire(
                                'Ooops!',
                                'No products listed',
                                'info'
                            )
                            this.$Progress.fail();
                        }
                        else{
                            //Send request to Server
                            axios.post('api/checkOut', {
                                client_id: this.chosen_client.id,
                                cart_content: JSON.stringify(this.carts),
                                total_amount: this.totalprice,
                                branch_id:'1',
                            })
                            .then(()=>{
                                Fire.$emit('AfterCreatedClient');
                                swal.fire(
                                    'Successful!',
                                    'You checked out successfully. Please check the record in the invoice tab.',
                                    'success'
                                )

                                this.clearCart();

                                this.$Progress.finish();

                            })
                            .catch(()=>{
                                swal.fire(
                                    'Ooops!',
                                    'It seems you have a problem regarding your inputs. Please, check carefully.',
                                    'info'
                                )
                                this.$Progress.fail();
                            })
                        }

                    }
                })




            }
        },

        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findProduct?q=' + query)
                .then((data)=>{
                    this.products = data.data
                })
                .catch(()=>{

                })
            })
            this.loadProducts();
            this.loadClient();
            Fire.$on('AfterCreatedClient', () => {
                this.loadProducts();
            });
            this.viewCart();

        }
    }
</script>
