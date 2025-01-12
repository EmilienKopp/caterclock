import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, IReport, ITimeLog } from '$models';

export class ReportBase implements IReport {
    id = $state<number>(0);
    created_at? = $state<any>();
    updated_at? = $state<any>();

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: IReport) {
        this.id = data.id;
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