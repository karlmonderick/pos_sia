<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdmin()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Invoice Lists</h3>

                <div class="card-tools">

                </div>

              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <b-table
                    :items="cashOuts"
                    :fields="fields"
                    :sort-by.sync="sortBy"
                    :sort-desc.sync="sortDesc"
                    :small="true"
                    :hover="true"
                    >
                    <template slot="cart" slot-scope="data">
                        <table class="table table-sm table-bordered">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Sub Total</th>
                            </tr>
                            <tr v-for="carts in convertJSON(data.item.cart)" :key="carts.id">
                                <td>{{carts.name}} </td>
                                <td>{{carts.price}} </td>
                                <td>{{carts.qty}} </td>
                                <td>{{carts.amount}}</td>
                            </tr>
                        </table>
                    </template>
                    <template slot="option" slot-scope="data">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-danger" href="#" @click="deleteInvoice(data.item.id)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </template>
                </b-table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="row justify-content-center" v-else>
            <not-found></not-found>
        </div>




    </div>


</template>

<script>


    export default {
        data() {
            return {
                sortBy: 'age',
                sortDesc: false,
                fields: [
                    { key: 'created_at', label: 'Date',sortable: true },
                    { key: 'receipt_no', sortable: true },
                    { key: 'full_name', sortable: true },
                    { key: 'cart', label: 'Orders', sortable: false },
                    { key: 'total_amount', label: 'Total', sortable: true },
                    { key: 'name', label: 'Branch', sortable: true },
                    { key: 'option', label: 'Option', sortable: false },
                ],
                cashOuts : [],
            }
        },
        methods: {
            convertJSON(cart){
                cart = JSON.parse(cart);
                // console.log(cart);
                return cart;
            },

            deleteInvoice($id){
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
                            this.form.get('api/invoice_list/').then(()=>{//NOT WORKING WTH TypeError: Cannot read property 'get' of undefined
                                swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                                )

                            Fire.$emit('AfterDeleteInvoice');
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

            loadCashOuts(){
                if(this.$gate.isAdmin()){
                    axios.get("api/invoice_list").then(({data}) => (this.cashOuts = data));
                }
            },

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

            this.loadCashOuts();

            Fire.$on('AfterDeleteInvoice', () => {
                this.loadCashOuts();
            });
        }
    }
</script>
