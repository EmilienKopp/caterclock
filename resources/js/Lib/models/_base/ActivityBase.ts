import { Project, User, TaskCategory, Task, Activity } from '$models';

export class ActivityBase implements Activity {
    id: number;
    project_id: number;
    project?: Project;
    user_id: number;
    user?: User;
    task_category_id: number;
    task_category?: TaskCategory;
    task_id?: number;
    task?: Task;
    date?: Date;
    comment?: string;
    start_time?: any;
    end_time?: any;
    duration?: number;
    rate?: number;
    created_at?: any;
    updated_at?: any;


    constructor(data: Activity) {
        this.id = data.id;
        this.project_id = data.project_id;
        this.user_id = data.user_id;
        this.task_category_id = data.task_category_id;
        this.task_id = data.task_id;
        this.date = data.date;
        this.comment = data.comment;
        this.start_time = data.start_time;
        this.end_time = data.end_time;
        this.duration = data.duration;
        this.rate = data.rate;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}