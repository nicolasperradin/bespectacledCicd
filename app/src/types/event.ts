import type { Item } from './item'
import type { User } from './user'
import type { Venue } from './venue'
import type { Schedule } from './schedule'

export interface Event extends Item {
	title: string
	type: string
	price: number
	src?: string
	venue?: Venue
	artists: User[]
	schedules: Schedule[]
}