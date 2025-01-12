import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITimeLog, IUser } from '$models';

export class TimeLogBase implements ITimeLog {
    id = $state<number>(0);
    user_id = $state<number>(0);
    user?: IUser;
    project_id = $state<number>(0);
    project?: IProject;
    date = $state<any>();
    in_time = $state<any>();
    out_time? = $state<any>();
    break_start? = $state<any>();
    break_end? = $state<any>();
    break_duration? = $state<number>(0);
    total_duration? = $state<number>(0);
    is_running = $state<boolean>(false);
    notes? = $state<string>('');
    created_at? = $state<any>();
    updated_at? = $state<any>();
    timezone? = $state<any>();

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: ITimeLog) {
        this.id = data.id;
        this.user_id = data.user_id;
        this.project_id = data.project_id;
        this.date = data.date;
        this.in_time = data.in_time;
        this.out_time = data.out_time;
        this.break_start = data.break_start;
        this.break_end = data.break_end;
        this.break_duration = data.break_duration;
        this.total_duration = data.total_duration;
        this.is_running = data.is_running;
        this.notes = data.notes;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.timezone = data.timezone;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}