import type { IActivity, ICompany, IConnectionRequest, IIdentity, IProject, IRate, ITimeLog } from '$models';

export class CompanyBase implements ICompany {
    id: number;
    name: string;
    address?: string;
    contact_email: string;
    created_at?: any;
    updated_at?: any;
    representative_id: number;
    contact_phone?: any;
    employees_count?: number;
    is_public: boolean;
    is_active: boolean;
    code?: any;
    corporate_number?: any;

    projects?: IProject[];
    timeLogs?: ITimeLog[];
    activities?: IActivity[];
    companies?: ICompany[];
    connectionRequests?: IConnectionRequest[];
    rates?: IRate[];
    identities?: IIdentity[];


    constructor(data: Company) {
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