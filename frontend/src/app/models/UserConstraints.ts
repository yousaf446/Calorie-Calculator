import { UserSettings } from "./UserSettings";

export class UserConstraints {
    public user_id: string = "";
    public calories_consumed: number = 0;
    public budget_spent: number = 0;
    public user_settings: UserSettings = new UserSettings();

    constructor(values: Object = {}) {
        // Constructor initialization
        Object.assign(this, values);
      }
  }