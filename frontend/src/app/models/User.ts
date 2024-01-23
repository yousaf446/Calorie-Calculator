export class User {
    public id: string = "";
    public name: string = "";
    public role: string = "";
    public email: string = "";

    constructor(values: Object = {}) {
      // Constructor initialization
      Object.assign(this, values);
    }
  }