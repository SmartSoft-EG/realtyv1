import axios from 'axios'
import { defineStore } from 'pinia'
import type { RealtyUnit } from '@/composables'

export const useRealtyUnitStore = defineStore(
    {
        id: 'realty_unit',
        state: () => ({
            list: [],
            show_add_dialog: false,
        }),

        actions: {
            fetch() {
                axios.get('realty-unit').then((res) => {
                    this.list = res.data
                })
            },

            save(realtyUnit: any) {
                //update if have id
                if (realtyUnit.id > 0) {
                    axios.post(`realty-unit/${realtyUnit.id}`, realtyUnit).then((res) => {
                        this.list = this.list.filter((u: any) => u.id !== realtyUnit.id) //remove old row
                        this.list.unshift(realtyUnit) //prepend add new row
                        this.show_add_dialog = false
                    })
                }

                //create
                else {
                    axios.post('realty-unit', realtyUnit).then((res) => {
                        this.list.push(res.data)
                        this.show_add_dialog = false
                    })
                }
            },

            delete(id: number) {
                if (!confirm('are you sure to delete this item?')) return
                axios.delete('realty-unit/' + id).then(() => {
                    this.list = this.list.filter(s => s['id'] !== id)
                })
            },

        },

    })
