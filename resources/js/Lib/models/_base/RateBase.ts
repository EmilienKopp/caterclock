import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITaskCategory, ITimeLog, IUser } from '$models';

export class RateBase implements IRate {
    id: number;
    project_id: number;
    project?: IProject;
    user_id: number;
    user?: IUser;
    task_category_id?: number;
    task_category?: ITaskCategory;
    rate: number;
    currency: any;
    valid_from?: Date;
    valid_to?: Date;
    created_at?: any;
    updated_at?: any;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


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

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}