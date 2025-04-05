// interfaces/Pages.ts
export interface Page {
  path: string
  title: string
  icon?: string
  onMenu?: boolean
}

export interface Pages {
  [key: string]: Page
}
