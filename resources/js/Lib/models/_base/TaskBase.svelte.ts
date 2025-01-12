import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITask, ITimeLog } from '$models';

export class TaskBase implements ITask {
    id = $state<number>(0);
    project_id = $state<number>(0);
    project?: IProject;
    category_id = $state<number>(0);
    name = $state<string>('');
    description? = $state<string>('');
    rate? = $state<number>(0);
    estimated_time? = $state<any>();
    actual_time? = $state<any>();
    date? = $state<Date>();
    start_time? = $state<any>();
    end_time? = $state<any>();
    due_date? = $state<any>();
    status = $state<string>('');
    created_at? = $state<any>();
    updated_at? = $state<any>();

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: ITask) {
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