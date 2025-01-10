import { Company, User, Project } from '$models';

export class ProjectBase implements Project {
    id: number;
    company_id?: number;
    company?: Company;
    user_id?: number;
    user?: User;
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
    }
}