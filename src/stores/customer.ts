import axios from 'axios'
import { defineStore } from 'pinia'
//import { type Customer } from '@/composables'
export const useCustomerStore = defineStore(
    {
        id: 'customer',
        state: () => ({
            customers: [],
            show_add_dialog: false,
        }),

        getters: {

        },

        actions: {
            fetchCustomers() {
                axios.get('customer').then((res) => {
                    this.customers = res.data
                })
            },

            saveCustomer(customer: any) {
                //update
                if (customer.id !== 0) {
                    axios.put(`customer/${customer.id}`, customer).then((res) => {
                        this.customers = this.customers.filter((u: any) => u.id !== customer.id) //remove old row
                        this.customers.unshift(customer) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('customer', customer).then((res) => {
                        this.customers.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },
            deleteCustomer(id: number) {
            //if (!confirm('are you sure to delete this item?')) return
                axios.delete('customer/' + id).then(() => {
                    this.customers = this.customers.filter(s => s['id'] !== id)
                })
            },
        },



    })
