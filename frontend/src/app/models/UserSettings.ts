export class UserSettings {
    public id: string = "";
    public user_id: string = "";
    public daily_calories: number = 0;
    public monthly_budget: number = 0;

    constructor(values: Object = {}) {
      // Constructor initialization
      Object.assign(this, values);
    }
  }