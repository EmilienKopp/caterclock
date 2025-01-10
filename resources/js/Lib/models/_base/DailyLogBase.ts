import { User, Project, DailyLog } from '$models';

export class DailyLogBase implements DailyLog {
    user_id?: number;
    user?: User;
    user_name?: any;
    project_id?: number;
    project?: Project;
    project_name?: string;
    date?: any;
    total_seconds?: number;
    total_minutes?: number;
    is_running?: boolean;


    constructor(data: DailyLog) {
        this.user_id = data.user_id;
        this.user_name = data.user_name;
        this.project_id = data.project_id;
        this.project_name = data.project_name;
        this.date = data.date;
        this.total_seconds = data.total_seconds;
        this.total_minutes = data.total_minutes;
        this.is_running = data.is_running;
    }
}