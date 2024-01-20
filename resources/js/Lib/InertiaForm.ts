

export class InertiaForm extends HTMLFormElement {
    errors: { [key: string]: string };
    data : {
        [key: string]: any;
    }

    constructor(data: {[key: string]: any} = {}) {
        super();
        this.errors = {};
        this.data = data;
    }

    reset(fields: string[] = []) {
        if (fields.length) {
            fields.forEach(field => {
                this.data[field] = '';
            });
        } else {
            this.data = {};
        }
        this.errors = {};
        super.reset();
    }


}