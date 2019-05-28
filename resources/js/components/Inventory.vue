<template>
    <div class="container-fluid">
        <div class="row mt-5" v-if="$gate.isAdmin()">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Overview</h3>

                        <!-- <div class="card-tools">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <li class="page-item"><a class="page-link" href="#">«</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">»</a></li>
                            </ul>
                        </div> -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table">
                        <tbody><tr>
                            <th>Product Name</th>
                            <th>Stocks</th>
                            <th style="width: 40px">Label</th>
                        </tr>
                        <tr v-for="inventory_s in inventories.inventory_summary" :key="inventory_s.name">
                            <td>{{inventory_s.name}}</td>
                            <td>
                            <!-- <div class="progress progress-xs">
                                <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                            </div> -->
                                {{inventory_s.stock}}
                            </td>
                            <td><span class="badge bg-success">Good</span></td>
                        </tr>
                        </tbody></table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
          </div>
          <div class="col-md-9">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Inventory: Stocks</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New Stock <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Product Name</th>
                        <th>Stocks</th>
                        <th>Updated at</th>
                        <th>Updated by</th>
                        <th>Options</th>
                    </tr>

                    <tr v-for="inventory in inventories.inventory_product.data" :key="inventory.id">
                        <td>{{inventory.id}}</td>
                        <td>{{inventory.name}}</td>
                        <td>{{inventory.stock_quantity}}</td>
                        <td>{{inventory.updated_at | myDate}}</td>
                        <td>{{inventory.username}}</td>

                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-primary" @click="editModal(inventory)">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" href="#" @click="deleteProduct(inventory.id)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>

                        </td>
                    </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="inventories.inventory_product" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="row justify-content-center" v-else>
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNewInventory" tabindex="-1" role="dialog" aria-labelledby="addNewInventory" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">Update Product Form</h5>
                        <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">New Product Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateInventory() : createInventory()">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Name">Product Name</label>
                                    <!-- <select v-model="form.product_id" id="inputType" class="form-control" :class="{ 'is-invalid': form.errors.has('product_id') }">
                                        <option v-for="p_list in inventories.product_list" :key="p_list.id">{{plist.name}}</option>
                                    </select> -->
                                    <select v-model="form.product_id" class="form-control">
                                        <option value="" selected>--Select--</option>
                                        <option v-for="p_list in inventories.product_list" :key="p_list.id">{{p_list.name}}</option>
                                    </select>
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
                inventories : {},
                form: new Form({
                    product_id:'',
                    stock_quantiy: '',
                    user_id: '',
                })
            }
        },
        methods: {
            getResults(page = 1){
                axios.get('api/inventory?page=' + page)
                .then(response => {
                    this.inventories = response.data;
                });
            },

            editModal(inventory){
                this.editmode = true;
                this.form.reset();
                $('#addNewInventory').modal('show');
                this.form.fill(inventory);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNewInventory').modal('show');
            },

            deleteInventory(id){
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
                        this.form.delete('api/inventory/'+id).then(()=>{
                            swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )
                            Fire.$emit('AfterCreatedInventory');
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
            updateInventory(id){
                this.$Progress.start();

                this.form.put('api/inventory/'+this.form.id)
                .then(()=>{

                    $('#addNewInventory').modal('hide')
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )

                    this.$Progress.finish();
                    Fire.$emit('AfterCreatedInventory');

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
            loadInventory(){
                if(this.$gate.isAdmin()){
                    axios.get("api/inventory").then(({data}) => (this.inventories = data));
                }

            },
            createInventory(){
                this.$Progress.start();

                this.form.post('api/inventory')
                .then(()=>{
                    Fire.$emit('AfterCreatedInventory');
                    $('#addNewInventory').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'New Stocks Added Successfully!'
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
                axios.get('api/findInventory?q=' + query)
                .then((data)=>{
                    this.inventory = data.data
                })
                .catch(()=>{

                })
            })
            this.loadInventory();
            Fire.$on('AfterCreatedInventory', () => {
                this.loadInventory();
            });
        }
    }
</script>
