import axios from 'axios'
import { defineStore } from 'pinia'
import type { RealtyStore, Realty } from '~/composables/interfaces'

export const useRealtyStore = defineStore(
    {
        id: 'realty',
        state: (): RealtyStore => ({
            realty_list: [],
            realty_units: [],
            realty_reservations: [],
            show_add_dialog: false,
        }),

        actions: {
            fetchRealty() {
                axios.get('realty').then((res) => {
                    this.realty_list = res.data
                })
            },

            saveRealty(realty: any) {
                //update
                if (realty.id > 0) {
                    axios.put(`realty/${realty.id}`, realty).then((res) => {
                        //this.realty_list = this.realty_list.filter((u: any) => u.id !== realty.id) //remove old row
                        //this.realty_list.unshift(res.data) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('realty', realty).then((res) => {
                        this.realty_list.push(res.data)
                        this.show_add_dialog = false
                    })
                }
            },

            deleteRealty(id: number) {
                if (!confirm('are you sure to delete this item?')) return
                axios.delete('realty/' + id).then(() => {
                    this.realty_list = this.realty_list.filter(s => s['id'] !== id)
                })
            },

            //===========================================
            //realty unit actions
            fetchRealtyUnits() {
                axios.get('realty-unit').then((res) => {
                    this.realty_units = res.data
                })
            },

            saveRealtyUnit(realtyUnit: any) {
                //update if have id
                if (realtyUnit.id > 0) {
                    axios.put(`realty-unit/${realtyUnit.id}`, realtyUnit).then((res) => {
                        //this.realty_list = this.realty_list.filter((u: any) => u.id !== realty.id) //remove old row
                        //this.realty_list.unshift(res.data) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('realty-unit', realtyUnit).then((res) => {
                        this.realty_units.push(res.data)
                        this.show_add_dialog = false
                    })
                }
            },

            deleteRealtyUnit(id: number) {
                if (!confirm('are you sure to delete this item?')) return
                axios.delete('realty-unit/' + id).then(() => {
                    this.realty_units = this.realty_units.filter(s => s['id'] !== id)
                })
            },


            //===========================================
            //realty unit reservations


            fetchRealtyReservations() {
                axios.get('realty-unit-reservation').then((res) => {
                    this.realty_reservations = res.data
                })
            },

            saveRealtyReservation(reservation: any) {
                //update if have id
                if (reservation.id > 0) {
                    axios.put(`realty-unit-reservation/${reservation.id}`, reservation).then((res) => {
                        //this.realty_list = this.realty_list.filter((u: any) => u.id !== realty.id) //remove old row
                        //this.realty_list.unshift(res.data) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('realty-unit-reservation', reservation).then((res) => {
                        this.realty_reservations.push(res.data)
                        this.show_add_dialog = false
                    })
                }
            },

            deleteRealtyReservations(id: number) {
                if (!confirm('are you sure to delete this item?')) return
                axios.delete('realty-unit-reservation/' + id).then(() => {
                    this.realty_reservations = this.realty_reservations.filter(s => s['id'] !== id)
                })
            },


        },

    })
