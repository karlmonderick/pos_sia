<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users Table</h3>

                <div class="card-tools">
                    <button class="btn btn-success" data-toggle="modal" data-target="#addNewUser">Add New <i class="fas fa-user-plus fa-fw"></i></button>
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
                        <th>Registered At</th>
                        <th>Options</th>
                    </tr>

                    <tr v-for="user in users" :key="user.id">
                        <td>{{user.id}}</td>
                        <td>{{user.name}}</td>
                        <td>{{user.email}}</td>
                        <td>{{user.type | upText}}</td>
                        <td>{{user.created_at | myDate}}</td>
                        <td>
                            <a href="">
                                <i class="fa fa-edit orange"></i>
                            </a> |
                            <a href="">
                                <i class="fa fa-trash red"></i>
                            </a>

                        </td>
                    </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="addNewUser" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">New User Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createUser">
                        <div class="modal-body">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="Name">Name</label>
                                    <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Enter Name" :class="{ 'is-invalid': form.errors.has('name') }">
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
                            <button type="submit" class="btn btn-primary">Create</button>
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
                users : {},
                form: new Form({
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: ''
                })
            }
        },
        methods: {
            loadUsers(){
                axios.get("api/user").then(({data}) => (this.users = data.data));
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user');
                $('#addNewUser').modal('hide')
                toast.fire({
                    type: 'success',
                    title: 'User Created Successfully!'
                })
                this.$Progress.finish();
            }
        },
        created() {
            this.loadUsers();
        }
    }
</script>
