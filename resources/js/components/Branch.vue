<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdmin()">
             <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Branch Lists</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New Branch <i class="fas fa-user-plus fa-fw"></i></button>
                </div>

              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Options</th>
                    </tr>
                    <tr v-for="branch in branch_list.data" :key="branch.id">
                        <td>{{branch.name}}</td>
                        <td>{{branch.location}}</td>

                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-primary" @click="editModal(branch)">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" href="#" @click="deleteBranch(branch.id)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>

                        </td>
                    </tr>

                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="branch_list" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="row justify-content-center" v-else>
            <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNewBranch" tabindex="-1" role="dialog" aria-labelledby="addNewBranch" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">Update Product Form</h5>
                        <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">New Product Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateBranch() : createBranch()">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Branch Name</label>
                                    <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Enter Name (e.g. SIA - Sample Branch)" :class="{ 'is-invalid': form.errors.has('name') }">
                                    <has-error :form="form" field="name"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="inputLocation">Branch location</label>
                                    <textarea v-model="form.location" name="inputLocation" id="inputLocation" placeholder="Enter Location" cols="30" rows="10" class="form-control" :class="{ 'is-invalid': form.errors.has('location') }"></textarea>
                                    <has-error :form="form" field="location"></has-error>
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
                branch_list: {},
                form: new Form({
                    id:'',
                    name: '',
                    location: '',
                })
            }
        },
        methods: {
            getResults(page = 1){
                axios.get('api/branch?page=' + page)
                .then(response => {
                    this.branch_list = response.data;
                });
            },

            editModal(branch){
                this.editmode = true;
                this.form.reset();
                $('#addNewBranch').modal('show');
                this.form.fill(branch);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNewBranch').modal('show');
            },

            deleteBranch(id){
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
                        this.form.delete('api/branch/'+id).then(()=>{
                            swal.fire(
                            'Deleted!',
                            'Branch has been deleted.',
                            'success'
                            )
                            Fire.$emit('AfterCreatedBranch');
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
            updateBranch(id){
                this.$Progress.start();

                this.form.put('api/branch/'+this.form.id)
                .then(()=>{

                    $('#addNewBranch').modal('hide')
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )

                    this.$Progress.finish();
                    Fire.$emit('AfterCreatedBranch');

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
            loadBranches(){
                if(this.$gate.isAdmin()){
                    axios.get("api/branch").then(({data}) => (this.branch_list = data));
                }

            },
            createBranch(){
                this.$Progress.start();

                this.form.post('api/branch')
                .then(()=>{
                    Fire.$emit('AfterCreatedBranch');
                    $('#addNewBranch').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'Branch Added Successfully!'
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
                axios.get('api/findBranch?q=' + query)
                .then((data)=>{
                    this.branch_list = data.data
                })
                .catch(()=>{

                })
            })
            this.loadBranches();
            Fire.$on('AfterCreatedBranch', () => {
                this.loadBranches();
            });
        }
    }
</script>
