import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITimeLog, IUser } from '$models';

export class ProjectBase implements IProject {
    id: number;
    company_id?: number;
    company?: ICompany;
    user_id?: number;
    user?: IUser;
    name: string;
    description?: string;
    start_date?: Date;
    end_date?: Date;
    created_at?: any;
    updated_at?: any;
    budget_low?: number;
    budget_mid?: number;
    budget_high?: number;
    spent?: number;
    balance_low?: number;
    balance_mid?: number;
    balance_high?: number;
    currency: any;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: Project) {
        this.id = data.id;
        this.company_id = data.company_id;
        this.user_id = data.user_id;
        this.name = data.name;
        this.description = data.description;
        this.start_date = data.start_date;
        this.end_date = data.end_date;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.budget_low = data.budget_low;
        this.budget_mid = data.budget_mid;
        this.budget_high = data.budget_high;
        this.spent = data.spent;
        this.balance_low = data.balance_low;
        this.balance_mid = data.balance_mid;
        this.balance_high = data.balance_high;
        this.currency = data.currency;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}