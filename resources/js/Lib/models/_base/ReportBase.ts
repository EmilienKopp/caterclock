import { Report } from '$models';

export class ReportBase implements Report {
    id: number;
    created_at?: any;
    updated_at?: any;


    constructor(data: Report) {
        this.id = data.id;
        this.created_at = data.created_at;
        this.updated_at = data.updated_at;
    }
}