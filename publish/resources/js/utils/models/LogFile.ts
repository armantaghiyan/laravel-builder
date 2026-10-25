export interface LogFileEntry {
    name: string
    path: string
    is_directory: boolean
    size: number | null
    modified_at: number
}

export interface LogFilePreview {
    name: string
    path: string
    size: number
    modified_at: number
    content: string
    line_count: number
    requested_lines: number
    truncated: boolean
}
