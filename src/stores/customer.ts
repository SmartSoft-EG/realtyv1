import axios from 'axios'
import { defineStore } from 'pinia'
//import { type Customer } from '@/composables'
export const useCustomerStore = defineStore(
    {
        id: 'customer',
        state: () => ({
            list: [],
            show_add_dialog: false,
        }),

        getters: {

        },

        actions: {
            fetchCustomers() {
                axios.get('customer').then((res) => {
                    this.list = res.data
                })
            },

            saveCustomer(customer: any) {
                //update
                if (customer.id !== 0) {
                    axios.put(`customer/${customer.id}`, customer).then((res) => {
                        this.list = this.list.filter((u: any) => u.id !== customer.id) //remove old row
                        this.list.unshift(customer) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('customer', customer).then((res) => {
                        this.list.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },
            deleteCustomer(id: number) {

                axios.delete('customer/' + id).then(() => {
                    this.list = this.list.filter(s => s['id'] !== id)
                })
            },
        },



    })
