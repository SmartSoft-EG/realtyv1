
export interface Doc {
    id: number,
    path: string,
    name?: string,
    type?: string
}
export interface Reservation {
    id: number,
    user_id?: number,
    customer_id?: number,
    realty_unit_id?: number,
    start_date?: Date,
    end_date?: Date,
    price?: number,
    state?: 'active' | 'closed' | 'waiting' | undefined,
}

export interface RealtyUnit {
    id: number,
    name: string,
    reality_id: number,
    code: string,
    type?: 't1' | 't2' | 't3' | undefined,
    description?: string,
    floor?: number,
    docs?: Doc[],
    realty?: Realty,
    reservations?: Reservation

}


export interface Realty {
    id: number,
    name?: string,
    address?: string,
    description?: string,
    code?: string,
    unites?: RealtyUnit[],
    docs?: Doc[]
}

export interface RealtyStore {
    realty_list: Realty[],
    realty_units: RealtyUnit[],
    show_add_dialog: boolean
}

export interface Customer {
    id: number,
    name: string,
    telephone: string,
    address?: string,
    email?: string,
}
