import PasswordField from "@/components/Auth/PasswordField";
import { Button } from "@/ui/Buttons";
import { Form } from "@/ui/Fields";
import { Layout } from "@/ui/Layout";
import { NextPage } from "next";

const ForgotPassword: NextPage = () => {
  return (
    <Layout nosavearea>
      <div className="grid gap-16">
        <p className="flex justify-center items-end h-56 text-[32px] font-semibold">
          Forgot Password
        </p>
        <Form
          className="space-y-5"
          initialValue={{
            password: "",
            confirm_password: "",
          }}
          onSubmit={() => console.log("ok")}
        >
          {() => (
            <>
              <PasswordField
                label={
                  <>
                    Password<span className="text-red-500">*</span>
                  </>
                }
                name={"password"}
              />
              <PasswordField
                label={
                  <>
                    Confirm Password<span className="text-red-500">*</span>
                  </>
                }
                name={"confirm_password"}
              />
              <Button className="w-full py-4">Confirm</Button>
            </>
          )}
        </Form>
      </div>
    </Layout>
  );
};

export default ForgotPassword;
