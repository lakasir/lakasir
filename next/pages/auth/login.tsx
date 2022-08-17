import type { NextPage } from "next";
import { FormEvent } from "react";
import PasswordField from "../../components/Auth/PasswordField";
import Layout from "../../components/Layout";
import { Button } from "../../components/Ui/Buttons/Button";
import Checkbox from "../../components/Ui/Fields/Checkbox";
import Form from "../../components/Ui/Fields/Form";
import Input from "../../components/Ui/Fields/Input";

const Login: NextPage = () => {
  const loginSubmit = (e: FormEvent, values: any) => {
    console.log(values);
  };
  return (
    <Layout>
      <div className="mx-auto w-11/12">
        <div className="grid gap-16">
          <p className="flex justify-center items-end h-56 text-[32px] font-semibold">
            Sign In <span className="ml-2 text-lakasir-primary"> LAKASIR</span>
          </p>
          <Form
            className="space-y-5"
            initialValue={{
              email: "sheenazien08@gmail.com",
              password: "",
            }}
            onSubmit={loginSubmit}
          >
            {() => (
              <>
                <Input
                  name={"email"}
                  type={"text"}
                  label={
                    <>
                      Username or Email<span className="text-red-500">*</span>
                    </>
                  }
                />
                <PasswordField />
                <Checkbox name={"remember_me"} label={"Remember Me"} />
                <Button className="w-full py-4">Sign in</Button>
              </>
            )}
          </Form>
        </div>
      </div>
    </Layout>
  );
};

export default Login;
