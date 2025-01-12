import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITask, ITaskCategory, ITimeLog, IUser } from '$models';

export class ActivityBase implements IActivity {
    id = $state<number>(0);
    project_id = $state<number>(0);
    project?: IProject;
    user_id = $state<number>(0);
    user?: IUser;
    task_category_id = $state<number>(0);
    task_category?: ITaskCategory;
    task_id? = $state<number>(0);
    task?: ITask;
    date? = $state<Date>();
    comment? = $state<string>('');
    start_time? = $state<any>();
    end_time? = $state<any>();
    duration? = $state<number>(0);
    rate? = $state<number>(0);
    created_at? = $state<any>();
    updated_at? = $state<any>();
    hours? = $state<number>(0);
    minutes? = $state<number>(0);

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];

    public plain() {
        return {
            id: this.id,
            project_id: this.project_id,
            user_id: this.user_id,
            task_category_id: this.task_category_id,
            task_id: this.task_id,
            date: this.date,
            comment: this.comment,
            start_time: this.start_time,
            end_time: this.end_time,
            duration: this.duration,
            rate: this.rate,
            created_at: this.created_at,
            updated_at: this.updated_at,
            hours: this.hours,
            minutes: this.minutes,
        };
    }


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