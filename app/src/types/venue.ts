import type { Item } from './item'
import type { Event } from './event'

export interface Venue extends Item {
	name: string
	type: string
	seats: number
	price: number
	src?: string
	events?: Event[]
}