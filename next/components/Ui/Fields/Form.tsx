import { FormEvent, useEffect, useLayoutEffect } from "react";

export interface IForm {
  onSubmit: (e: FormEvent, values: any) => void;
  children: () => JSX.Element;
  method?: string;
  id?: string;
  className?: string;
  initialValue: any;
}

const setDefaultFormValue = (initialValue: any) => {
  const formElement = document.getElementById("form__lakasir");
  // set default value for form element via dom formElement object
  formElement?.querySelectorAll("input").forEach((input) => {
    if (["radio", "checkbox"].includes(input.type)) {
      input.checked = initialValue[input.name];
    } else {
      input.value = initialValue[input.name];
    }
  });
};

const Form = (props: IForm): JSX.Element => {
  useLayoutEffect(() => {
    setDefaultFormValue(props.initialValue);
  }, []);

  return (
    <div>
      <form
        className={props.className}
        id="form__lakasir"
        method={props.method ?? "post"}
        onSubmit={(e) => {
          e.preventDefault();
          const formValues = Object.fromEntries(new FormData(e.target as HTMLFormElement));
          console.log(formValues);
          setDefaultFormValue(formValues);
          props.onSubmit(e, formValues);
        }}
      >
        {props.children()}
      </form>
    </div>
  );
};

export { Form };
