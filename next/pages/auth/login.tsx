import type { NextPage } from "next";
import { FormEvent } from "react";
import Button from "../../components/Buttons/Button";
import Checkbox from "../../components/Fields/Checkbox";
import Form from "../../components/Fields/Form";
import Input from "../../components/Fields/Input";

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
          password: "",
          "remember-me": true,
        }}
        onSubmit={loginSubmit}
      >
        {() => (
          <>
            <Input name={"email"} type={"text"} label="Username or Email" />
            <Input name={"password"} type={"password"} label="Password" />
            <Checkbox name={"remember-me"} label="Remember Me"/>
            <Button className="w-full py-4">Sign in</Button>
          </>
        )}
      </Form>
    </div>
  );
};

export default Login;
