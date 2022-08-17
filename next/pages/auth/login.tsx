import type { NextPage } from "next";
import { FormEvent } from "react";
import PasswordField from "../../components/Auth/PasswordField";
import { Button } from "../../components/Ui/Buttons/Button";
import Form from "../../components/Ui/Fields/Form";
import Input from "../../components/Ui/Fields/Input";

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
        }}
        onSubmit={loginSubmit}
      >
        {() => (
          <>
            <Input name={"email"} type={"text"} label="Username or Email" />
            <PasswordField/>
            <Button className="w-full py-4">Sign in</Button>
          </>
        )}
      </Form>
    </div>
  );
};

export default Login;
