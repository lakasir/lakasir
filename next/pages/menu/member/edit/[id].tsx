import { Layout } from "@/ui/Layout";
import { NextPage } from "next";
import FormMember from "../form";

const EditMember: NextPage = () => {
  return (
    <Layout title="Edit Member" back={true}>
      <div className="py-3">
        <FormMember />
      </div>
    </Layout>
  );
};

export default EditMember;
