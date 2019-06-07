<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdmin()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i></button>
                </div>

              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Branch</th>
                        <th>Registered At</th>
                        <th>Options</th>
                    </tr>

                    <tr v-for="user in users.data" :key="user.id">
                        <td>{{user.id}}</td>
                        <td>{{user.username}}</td>
                        <td>{{user.email}}</td>
                        <td>{{user.type | upText}}</td>
                        <td>{{user.branch_name}} <p v-show="!user.branch_name">None</p> </td>
                        <td>{{user.created_at | myDate}}</td>
                        <td>
                            <button class="btn btn-xs btn-primary" @click="editModal(user)">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button class="btn btn-xs btn-danger" href="#" @click="deleteUser(user.id)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="row justify-content-center" v-else>
            <not-found></not-found>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="addNewUser" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-show="editmode" class="modal-title" id="exampleModalLabel">Update User Form</h5>
                        <h5 v-show="!editmode" class="modal-title" id="exampleModalLabel">New User Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Name">Name</label>
                                    <input v-model="form.username" type="text" class="form-control" id="name" placeholder="Enter Name" :class="{ 'is-invalid': form.errors.has('username') }">
                                    <has-error :form="form" field="username"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input v-model="form.email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" :class="{ 'is-invalid': form.errors.has('email') }">
                                    <has-error :form="form" field="email"></has-error>
                                </div>

                                <div class="form-group">
                                    <label for="inputType">Type</label>
                                    <select v-model="form.type" id="inputType" class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                        <option selected>Admin</option>
                                        <option>Manager</option>
                                        <option>Secretary</option>
                                    </select>
                                    <has-error :form="form" field="type"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="inputType">Branch</label>
                                        <select v-model="form.branch_id" class="form-control">
                                            <option value="" selected>--Select--</option>
                                            <option v-for="branch_list in branch.data" :key="branch_list.id" :value="branch_list.id" >{{branch_list.name}}</option>
                                        </select>
                                    <has-error :form="form" field="type"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="inputBio">Bio</label>
                                    <textarea v-model="form.bio" name="Bio" id="" cols="30" rows="10" class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                    <has-error :form="form" field="bio"></has-error>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">File input</label>
                                    <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="">Upload</span>
                                    </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input v-model="form.password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                                    <has-error :form="form" field="password"></has-error>
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
                users : {},
                branch: {},
                form: new Form({
                    id:'',
                    username: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                    branch_id: ''
                })
            }
        },
        methods: {
            getResults(page = 1){
                axios.get('api/user?page=' + page)
                .then(response => {
                    this.users = response.data;
                });
            },
            loadBranch(){
                if(this.$gate.isAdmin()){
                    axios.get("api/branch").then(({data}) => (this.branch = data));
                }

            },
            updateUser(id){
                this.$Progress.start();

                this.form.put('api/user/'+this.form.id)
                .then(()=>{

                    $('#addNewUser').modal('hide')
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )

                    this.$Progress.finish();
                    Fire.$emit('AfterCreatedUser');

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
            editModal(user){
                this.editmode = true;
                this.form.reset();
                $('#addNewUser').modal('show');
                this.form.fill(user);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNewUser').modal('show');
            },

            deleteUser(id){
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
                        this.form.delete('api/user/'+id).then(()=>{
                            swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )
                            Fire.$emit('AfterCreatedUser');
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
            loadUsers(){
                if(this.$gate.isAdmin()){
                    axios.get("api/user").then(({data}) => (this.users = data));
                }

            },
            createUser(){
                this.$Progress.start();

                this.form.post('api/user')
                .then(()=>{
                    Fire.$emit('AfterCreatedUser');
                    $('#addNewUser').modal('hide')
                    toast.fire({
                        type: 'success',
                        title: 'User Created Successfully!'
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
                axios.get('api/findUser?q=' + query)
                .then((data)=>{
                    this.users = data.data
                })
                .catch(()=>{

                })
            })
            this.loadUsers();
            this.loadBranch();
            Fire.$on('AfterCreatedUser', () => {
                this.loadUsers();
            });
        }
    }
</script>
