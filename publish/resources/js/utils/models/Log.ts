export default interface Log {
    id: string,
	user_id: string,
	user_guard: string,
	user_guard_text: string,
    user_guard_color: string,

	event: string,
    event_text: string,

    level: string,
    level_text: string,
    level_color: string,

	message: string,
	loggable_type: string,
	loggable_type_text: string,
	loggable_id: string,
	metadata: string,
	ip_address: string,
	user_agent: string,
	is_reviewed: number,
    is_reviewed_text: string,
	created_at: string,
	updated_at: string,
}
