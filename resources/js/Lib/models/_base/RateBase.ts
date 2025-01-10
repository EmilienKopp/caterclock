import { Project, User, TaskCategory, Rate } from '$models';

export class RateBase implements Rate {
    id: number;
    project_id: number;
    project?: Project;
    user_id: number;
    user?: User;
    task_category_id?: number;
    task_category?: TaskCategory;
    rate: number;
    currency: any;
    valid_from?: Date;
    valid_to?: Date;
    created_at?: any;
    updated_at?: any;


    constructor(data: Rate) {
        this.id = data.id;
        this.project_id = data.project_id;
        this.user_id = data.user_id;
        this.task_category_id = data.task_category_id;
        this.rate = data.rate;
        this.currency = data.currency;
        this.valid_from = data.valid_from;
        this.valid_to = data.valid_to;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}