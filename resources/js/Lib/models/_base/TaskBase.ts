import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITask, ITimeLog } from '$models';

export class TaskBase implements ITask {
    id: number;
    project_id: number;
    project?: IProject;
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

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


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

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}