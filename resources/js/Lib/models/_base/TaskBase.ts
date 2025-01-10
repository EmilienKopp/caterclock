import { Project, Task } from '$models';

export class TaskBase implements Task {
    id: number;
    project_id: number;
    project?: Project;
    category_id: number;
    name: string;
    description?: string;
    rate?: number;
    estimated_time?: any;
    actual_time?: any;
    date?: Date;
    start_time?: any;
    end_time?: any;
    due_date?: any;
    status: string;
    created_at?: any;
    updated_at?: any;


    constructor(data: Task) {
        this.id = data.id;
        this.project_id = data.project_id;
        this.category_id = data.category_id;
        this.name = data.name;
        this.description = data.description;
        this.rate = data.rate;
        this.estimated_time = data.estimated_time;
        this.actual_time = data.actual_time;
        this.date = data.date;
        this.start_time = data.start_time;
        this.end_time = data.end_time;
        this.due_date = data.due_date;
        this.status = data.status;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}