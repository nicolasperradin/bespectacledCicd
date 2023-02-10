<template>
    <div class="forget-password">
        <h1>Forget Password</h1>
        <Form @submit="forgotPassword" :validation-schema="schema">
            <div class="form-group">
                <label for="email">Email</label>
                <Field name="email" type="email" class="form-control" />
                <ErrorMessage name="email" class="error-feedback text-danger" />
                <span class="text-danger">{{ errors['email'] }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-block" :disabled="loading">
                    <span v-show="loading" class="spinner-border spinner-border-sm"></span>
                    <span>Forget Password</span>
                </button>
            </div>
            <div class="form-group">
                <div v-if="message" class="alert alert-danger" role="alert">
                    {{ message }}
                </div>
            </div>
        </Form>
    </div>
</template>
<script>
import { Form, Field, ErrorMessage } from "vee-validate";
import * as yup from "yup";
export default {
    name: "ForgotPassword",
    components: {
        Form,
        Field,
        ErrorMessage,
    },
    data() {
        var errors = { "email": "", "password": "" };
        const schema = yup.object().shape({
            email: yup
                .string()
                .required("Email is required!")
                .email("Email is invalid!")
                .max(50, "Must be maximum 50 characters!"),
        });
        return {
            loading: false,
            message: "",
            schema,
            errors,
        };
    },
    methods: {
        forgotPassword(email) {
            console.log(email);
            this.loading = true;
            this.$store.dispatch("auth/forget-password", email).then(
                () => {
                    this.$router.push("/login");
                },
                (error) => {
                    this.loading = false;
                    this.message =
                        (error.response &&
                            error.response.data &&
                            error.response.data.message) ||
                        error.message ||
                        error.toString();

                    if (error.response.data.error) {
                        this.errors['email'] = error.response.data.error
                    } else if (error.response.data.violations) {
                        error.response.data.violations.forEach(element =>
                            this.errors[element.propertyPath] = element.message
                        );
                    }
                }
            );
        },
        handleLogin(user) {
            this.loading = true;
            this.$vg.dispatch("auth/login", user).then(
                () => {
                    this.$router.push("/profile");
                },
                (error) => {
                    this.loading = false;
                    this.message =
                        (error.response &&
                            error.response.data &&
                            error.response.data.message) ||
                        error.message ||
                        error.toString();

                    if (error.response.data.error) {
                        this.errors['email'] = error.response.data.error
                    } else if (error.response.data.violations) {
                        error.response.data.violations.forEach(element =>
                            this.errors[element.propertyPath] = element.message
                        );
                    }
                }
            );
        }
    },
};
</script>