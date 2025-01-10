import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITaskCategory, ITimeLog } from '$models';

export class TaskCategoryBase implements ITaskCategory {
    id: number;
    name: string;
    description?: string;
    created_at?: any;
    updated_at?: any;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: TaskCategory) {
        this.id = data.id;
        this.name = data.name;
        this.description = data.description;
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