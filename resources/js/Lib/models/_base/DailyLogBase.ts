import type { IActivity, ICompany, IConnectionRequest, IDailyLog, IIdentity, IProject, IRate, ITimeLog, IUser } from '$models';

export class DailyLogBase implements IDailyLog {
    user_id?: number;
    user?: IUser;
    user_name?: any;
    project_id?: number;
    project?: IProject;
    project_name?: string;
    date?: any;
    total_seconds?: number;
    total_minutes?: number;
    is_running?: boolean;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: DailyLog) {
        this.user_id = data.user_id;
        this.user_name = data.user_name;
        this.project_id = data.project_id;
        this.project_name = data.project_name;
        this.date = data.date;
        this.total_seconds = data.total_seconds;
        this.total_minutes = data.total_minutes;
        this.is_running = data.is_running;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}