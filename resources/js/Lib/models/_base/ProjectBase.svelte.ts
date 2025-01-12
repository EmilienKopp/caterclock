import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITimeLog, IUser } from '$models';

export class ProjectBase implements IProject {
    id = $state<number>(0);
    company_id? = $state<number>(0);
    company?: ICompany;
    user_id? = $state<number>(0);
    user?: IUser;
    name = $state<string>('');
    description? = $state<string>('');
    start_date? = $state<Date>();
    end_date? = $state<Date>();
    created_at? = $state<any>();
    updated_at? = $state<any>();
    budget_low? = $state<number>(0);
    budget_mid? = $state<number>(0);
    budget_high? = $state<number>(0);
    spent? = $state<number>(0);
    balance_low? = $state<number>(0);
    balance_mid? = $state<number>(0);
    balance_high? = $state<number>(0);
    currency = $state<any>();

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: IProject) {
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