import type { IActivity, ICompany, IConnectionRequest, IIdentity, IPosition, IProject, IRate, IRole, ITimeLog, IUser } from '$models';

export class PositionBase implements IPosition {
    company_id: number;
    company?: ICompany;
    user_id: number;
    user?: IUser;
    created_at?: any;
    updated_at?: any;
    role_id: number;
    role?: IRole;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: Position) {
        this.company_id = data.company_id;
        this.user_id = data.user_id;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.role_id = data.role_id;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}