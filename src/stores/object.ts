import axios from 'axios'
import { defineStore } from 'pinia'
//import { type Customer } from '@/composables'
export const useObjectStore = defineStore(
    {
        id: 'object',
        state: () => ({
            list: [],

            show_add_dialog: false,
        }),

        getters: {
            type: state => (type: string) => state.list.filter(p => p.type == type),

            customers: state => state.list.filter(p => p.type == 'customer'),
            suppliers: state => state.list.filter(p => p.type == 'supplier'),
            owners: state => state.list.filter(p => p.type == 'owner'),
            employees: state => state.list.filter(p => p.type == 'employee'),
            stores: state => state.list.filter(p => p.type == 'store'),

        },

        actions: {
            fetch(type: string) {
                axios.get('acc-object?type=' + type).then((res) => {
                    var data = [...this.list, ...res.data]
                    this.list = [...new Set(data)]
                })
            },
            fetchAll() {
                axios.get('acc-object?all=true').then((res) => {
                    this.list = res.data
                })
            },

            save(object: any) {
                //update
                if (object.id !== 0) {
                    axios.put(`acc-object/${object.id}`, object).then((res) => {
                        this.list = this.list.filter((u: any) => u.id !== object.id) //remove old row
                        this.list.unshift(object) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('acc-object', object).then((res) => {
                        this.list.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },

            delete(id: number) {
                axios.delete('acc-object/' + id).then(() => {
                    this.list = this.list.filter(s => s['id'] !== id)
                })
            },
        },



    })
