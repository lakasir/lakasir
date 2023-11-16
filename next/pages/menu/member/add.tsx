import { NextPage } from "next";
import Layout from "../../../components/Ui/Layout";
import FormMember from "./form";

const AddMember: NextPage = () => {
  return (
    <Layout title="Add Member" back={true}>
      <div className="py-3">
      <FormMember />
      </div>
    </Layout>
  );
};

export default AddMember;
