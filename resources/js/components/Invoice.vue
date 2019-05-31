<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdmin()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Invoice Lists</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add Product <i class="fas fa-user-plus fa-fw"></i></button>
                </div>

              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Options</th>
                    </tr>

                    <tr v-for="product in products.data" :key="product.id">
                        <td>{{product.id}}</td>
                        <td>{{product.name}}</td>
                        <td>{{product.price}}</td>
                        <td>{{product.description | upText}}</td>

                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-primary" @click="editModal(product)">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" href="#" @click="deleteProduct(product.id)">
                                    <i class="fa fa-trash"></i>
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


        <!-- Modal -->
        <div class="modal fade" id="addNewProduct" tabindex="-1" role="dialog" aria-labelledby="addNewProduct" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">Update Product Form</h5>
                        <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">New Product Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateProduct() : createProduct()">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Name">Product Name</label>
                                    <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Enter Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="productname"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="Product_Price">Retail Price</label>
                                    <input v-model="form.price" type="number" class="form-control" id="Product_Price" placeholder="Enter Price" :class="{ 'is-invalid': form.errors.has('price') }">
                                    <has-error :form="form" field="price"></has-error>
                                </div>

                                <div class="form-group">
                                    <label for="inputDescription">Product Description</label>
                                    <textarea v-model="form.description" name="inputDescription" id="inputDescription" placeholder="Enter Description" cols="30" rows="10" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                                    <has-error :form="form" field="description"></has-error>
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
                form: new Form({
                    id:'',
                    name: '',
                    price: '',
                    description: '',
                })
            }
        },
        methods: {
            getResults(page = 1){
                axios.get('api/product?page=' + page)
                .then(response => {
                    this.products = response.data;
                });
            },

            editModal(product){
                this.editmode = true;
                this.form.reset();
                $('#addNewProduct').modal('show');
                this.form.fill(product);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNewProduct').modal('show');
            },

            deleteProduct(id){
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                    if (result.value) {
                        //Send request to Server
                        this.form.delete('api/product/'+id).then(()=>{
                            swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )
                            Fire.$emit('AfterCreatedProduct');
                        }).catch(()=>{
                            swal.fire(
                            'Failed!',
                            'There was something wrong.',
                            'warning'
                            )
                        })



                    }
                })
            },
            updateProduct(id){
                this.$Progress.start();

                this.form.put('api/product/'+this.form.id)
                .then(()=>{

                    $('#addNewProduct').modal('hide')
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )

                    this.$Progress.finish();
                    Fire.$emit('AfterCreatedProduct');

                })
                .catch(()=>{
                    swal.fire(
                        'Error!',
                        'Please check your inputs.',
                        'warning'
                    )
                    this.$Progress.fail();
                })
            },
            loadProducts(){
                if(this.$gate.isAdmin()){
                    axios.get("api/product").then(({data}) => (this.products = data));
                }

            },
            createProduct(){
                this.$Progress.start();

                this.form.post('api/product')
                .then(()=>{
                    Fire.$emit('AfterCreatedProduct');
                    $('#addNewProduct').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Product Created Successfully!'
                    })

                    this.$Progress.finish();

                })
                .catch(()=>{
                    toast.fire({
                        type: 'error',
                        title: 'Error, Check your Inputs!'
                    })
                    this.$Progress.fail();
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
            Fire.$on('AfterCreatedProduct', () => {
                this.loadProducts();
            });
        }
    }
</script>
