import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITask, ITaskCategory, ITimeLog, IUser } from '$models';

export class ActivityBase implements IActivity {
    id: number;
    project_id: number;
    project?: IProject;
    user_id: number;
    user?: IUser;
    task_category_id: number;
    task_category?: ITaskCategory;
    task_id?: number;
    task?: ITask;
    date?: Date;
    comment?: string;
    start_time?: any;
    end_time?: any;
    duration?: number;
    rate?: number;
    created_at?: any;
    updated_at?: any;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


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

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}