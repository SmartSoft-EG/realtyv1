import axios from 'axios'
import { defineStore } from 'pinia'
//import { type Customer } from '@/composables'
export const usePeopleStore = defineStore(
    {
        id: 'people',
        state: () => ({
            list: [],

            show_add_dialog: false,
        }),

        getters: {
            customers: state => state.list.filter(p => p.type == 'customer'),
            suppliers: state => state.list.filter(p => p.type == 'supplier'),
            owners: state => state.list.filter(p => p.type == 'owner'),

        },

        actions: {
            fetch(type: string) {
                axios.get('people?type=' + type).then((res) => {
                    var data = [...this.list, ...res.data]
                    this.list = [...new Set(data)]
                })
            },
            fetchAll() {
                axios.get('people?all=true').then((res) => {
                    this.list = res.data
                })
            },

            save(people: any) {
                //update
                if (people.id !== 0) {
                    axios.put(`people/${people.id}`, people).then((res) => {
                        this.list = this.list.filter((u: any) => u.id !== people.id) //remove old row
                        this.list.unshift(people) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('people', people).then((res) => {
                        this.list.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },

            delete(id: number) {
                axios.delete('people/' + id).then(() => {
                    this.list = this.list.filter(s => s['id'] !== id)
                })
            },
        },



    })
