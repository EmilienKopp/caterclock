import { Company } from '$models';

export class CompanyBase implements Company {
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
    }
}