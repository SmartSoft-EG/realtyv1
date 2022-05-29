import axios from 'axios'
import { defineStore } from 'pinia'
//import { type Customer } from '@/composables'
export const useReservationStore = defineStore(
    {
        id: 'reservation',
        state: () => ({
            list: [],
            show_add_dialog: false,
        }),

        getters: {

        },

        actions: {
            fetch() {
                axios.get('realty-unit-reservation').then((res) => {
                    this.list = res.data
                })
            },

            save(reservation: any) {
                //update
                if (reservation.id !== 0) {
                    axios.put(`realty-unit-reservation/${reservation.id}`, reservation).then((res) => {
                        this.list = this.list.filter((u: any) => u.id !== reservation.id) //remove old row
                        this.list.unshift(reservation) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('realty-unit-reservation', reservation).then((res) => {
                        this.list.push(res.data)
                        this.show_add_dialog = false
                    })
                }

            },

            delete(id: number) {

                axios.delete('realty-unit-reservation/' + id).then(() => {
                    this.list = this.list.filter(s => s['id'] !== id)
                })
            },
        },



    })
