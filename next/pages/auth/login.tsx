import type { NextPage } from "next";
import { FormEvent } from "react";
import Button from "../../components/Buttons/Button";
import Checkbox from "../../components/Fields/Checkbox";
import Form from "../../components/Fields/Form";
import Input from "../../components/Fields/Input";
import Select from "../../components/Fields/Select";

const Login: NextPage = () => {
  const loginSubmit = (e: FormEvent, values: any) => {
    console.log(values);
  };
  return (
    <div className="p-2">
      <p>Sign In LAKASIR</p>
      <Form
        initialValue={{
          email: "sheenazien08@gmail.com",
          date: "2022-08-07",
          password: "",
          "remember-me": false,
          countries: "SGP",
          checkboxs: {
            nameOne: true,
            nameTwo: false,
            nameThree: true,
            nameFour: false,
            nameFive: true,
          }
        }}
        onSubmit={loginSubmit}
      >
        {() => (
          <>
            <Input name={"email"} type={"text"} label="Username or Email" />
            <Input name={"date"} type={"date"} label="Date" />
            <Input name={"password"} type={"password"} label="Password" />
            <Select
              name={"countries"}
              label="Countries"
              options={[
                { label: "Indonesia", value: "IDN" },
                { label: "Malaysia", value: "MLY" },
                { label: "Singapore", value: "SGP" },
              ]}
            />
            <Checkbox name={"remember-me"} label="Remember Me" />
            <Button className="w-full py-4">Sign in</Button>
          </>
        )}
      </Form>
    </div>
  );
};

export default Login;
