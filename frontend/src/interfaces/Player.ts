
export interface Player {
    hash: string
    name: string
    birth_date: string
    score: number
    address: {
      street: string
      city: string
      province: string
      postal_code: string
    }
    created_at?: string
    qr_code_path?: string
  }