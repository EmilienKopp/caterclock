import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITimeLog } from '$models';

export class CompanyBase implements ICompany {
    id = $state<number>(0);
    name = $state<string>('');
    address? = $state<string>('');
    contact_email = $state<string>('');
    created_at? = $state<any>();
    updated_at? = $state<any>();
    representative_id = $state<number>(0);
    contact_phone? = $state<any>();
    employees_count? = $state<number>(0);
    is_public = $state<boolean>(false);
    is_active = $state<boolean>(false);
    code? = $state<any>();
    corporate_number? = $state<any>();

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: ICompany) {
        this.id = data.id;
        this.name = data.name;
        this.address = data.address;
        this.contact_email = data.contact_email;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
        this.representative_id = data.representative_id;
        this.contact_phone = data.contact_phone;
        this.employees_count = data.employees_count;
        this.is_public = data.is_public;
        this.is_active = data.is_active;
        this.code = data.code;
        this.corporate_number = data.corporate_number;

        this.projects = data.projects ?? [];
        this.timeLogs = data.timeLogs ?? [];
        this.activities = data.activities ?? [];
        this.companies = data.companies ?? [];
        this.connectionRequests = data.connectionRequests ?? [];
        this.rates = data.rates ?? [];
        this.identities = data.identities ?? [];
    }
}