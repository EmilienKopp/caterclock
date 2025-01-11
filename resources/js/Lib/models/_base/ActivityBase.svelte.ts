import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITask, ITaskCategory, ITimeLog, IUser } from '$models';

export class ActivityBase implements IActivity {
    id: number = $state<number>(0);
    project_id: number = $state<number>(0);
    project?: IProject = $state<IProject>();
    user_id: number = $state<number>(0);
    user?: IUser = $state<IUser>();
    task_category_id: number = $state<number>(0);
    task_category?: ITaskCategory = $state<ITaskCategory>();
    task_id?: number = $state<number>(0);
    task?: ITask = $state<ITask>();
    date?: Date = $state<Date>();
    comment?: string = $state<string>('');
    start_time?: any = $state<any>();
    end_time?: any = $state<any>();
    duration?: number = $state<number>(0);
    rate?: number = $state<number>(0);
    created_at?: any = $state<any>();
    updated_at?: any = $state<any>();
    hours?: number = $state<number>(0);
    minutes?: number = $state<number>(0);

    projects?: IProject[] = $state<IProject[]>([]);
    timeLogs?: ITimeLog[] = $state<ITimeLog[]>([]);
    companies?: ICompany[] = $state<ICompany[]>([]);
    connectionRequests?: IConnectionRequest[] = $state<IConnectionRequest[]>([]);
    rates?: IRate[] = $state<IRate[]>([]);
    identities?: IIdentity[] = $state<IIdentity[]>([]);


    constructor(data: IActivity) {
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
        this.hours = data.hours;
        this.minutes = data.minutes;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}