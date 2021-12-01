<?php
// This file was auto-generated from sdk-root/src/data/servicecatalog/2015-12-10/api-2.json
return [ 'version' => '2.0', 'metadata' => [ 'apiVersion' => '2015-12-10', 'endpointPrefix' => 'servicecatalog', 'jsonVersion' => '1.1', 'protocol' => 'json', 'serviceFullName' => 'AWS Service Catalog', 'serviceId' => 'Service Catalog', 'signatureVersion' => 'v4', 'targetPrefix' => 'AWS242ServiceCatalogService', 'uid' => 'servicecatalog-2015-12-10', ], 'operations' => [ 'AcceptPortfolioShare' => [ 'name' => 'AcceptPortfolioShare', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'AcceptPortfolioShareInput', ], 'output' => [ 'shape' => 'AcceptPortfolioShareOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'LimitExceededException', ], ], ], 'AssociateBudgetWithResource' => [ 'name' => 'AssociateBudgetWithResource', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'AssociateBudgetWithResourceInput', ], 'output' => [ 'shape' => 'AssociateBudgetWithResourceOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'DuplicateResourceException', ], [ 'shape' => 'LimitExceededException', ], [ 'shape' => 'ResourceNotFoundException', ], ], ], 'AssociatePrincipalWithPortfolio' => [ 'name' => 'AssociatePrincipalWithPortfolio', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'AssociatePrincipalWithPortfolioInput', ], 'output' => [ 'shape' => 'AssociatePrincipalWithPortfolioOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'LimitExceededException', ], ], ], 'AssociateProductWithPortfolio' => [ 'name' => 'AssociateProductWithPortfolio', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'AssociateProductWithPortfolioInput', ], 'output' => [ 'shape' => 'AssociateProductWithPortfolioOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'LimitExceededException', ], ], ], 'AssociateServiceActionWithProvisioningArtifact' => [ 'name' => 'AssociateServiceActionWithProvisioningArtifact', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'AssociateServiceActionWithProvisioningArtifactInput', ], 'output' => [ 'shape' => 'AssociateServiceActionWithProvisioningArtifactOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'DuplicateResourceException', ], [ 'shape' => 'LimitExceededException', ], ], ], 'AssociateTagOptionWithResource' => [ 'name' => 'AssociateTagOptionWithResource', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'AssociateTagOptionWithResourceInput', ], 'output' => [ 'shape' => 'AssociateTagOptionWithResourceOutput', ], 'errors' => [ [ 'shape' => 'TagOptionNotMigratedException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'LimitExceededException', ], [ 'shape' => 'DuplicateResourceException', ], [ 'shape' => 'InvalidStateException', ], ], ], 'BatchAssociateServiceActionWithProvisioningArtifact' => [ 'name' => 'BatchAssociateServiceActionWithProvisioningArtifact', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'BatchAssociateServiceActionWithProvisioningArtifactInput', ], 'output' => [ 'shape' => 'BatchAssociateServiceActionWithProvisioningArtifactOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], ], ], 'BatchDisassociateServiceActionFromProvisioningArtifact' => [ 'name' => 'BatchDisassociateServiceActionFromProvisioningArtifact', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'BatchDisassociateServiceActionFromProvisioningArtifactInput', ], 'output' => [ 'shape' => 'BatchDisassociateServiceActionFromProvisioningArtifactOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], ], ], 'CopyProduct' => [ 'name' => 'CopyProduct', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CopyProductInput', ], 'output' => [ 'shape' => 'CopyProductOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'CreateConstraint' => [ 'name' => 'CreateConstraint', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CreateConstraintInput', ], 'output' => [ 'shape' => 'CreateConstraintOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'LimitExceededException', ], [ 'shape' => 'DuplicateResourceException', ], ], ], 'CreatePortfolio' => [ 'name' => 'CreatePortfolio', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CreatePortfolioInput', ], 'output' => [ 'shape' => 'CreatePortfolioOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'LimitExceededException', ], [ 'shape' => 'TagOptionNotMigratedException', ], ], ], 'CreatePortfolioShare' => [ 'name' => 'CreatePortfolioShare', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CreatePortfolioShareInput', ], 'output' => [ 'shape' => 'CreatePortfolioShareOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'LimitExceededException', ], [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'OperationNotSupportedException', ], [ 'shape' => 'InvalidStateException', ], ], ], 'CreateProduct' => [ 'name' => 'CreateProduct', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CreateProductInput', ], 'output' => [ 'shape' => 'CreateProductOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'LimitExceededException', ], [ 'shape' => 'TagOptionNotMigratedException', ], ], ], 'CreateProvisionedProductPlan' => [ 'name' => 'CreateProvisionedProductPlan', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CreateProvisionedProductPlanInput', ], 'output' => [ 'shape' => 'CreateProvisionedProductPlanOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidStateException', ], ], ], 'CreateProvisioningArtifact' => [ 'name' => 'CreateProvisioningArtifact', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CreateProvisioningArtifactInput', ], 'output' => [ 'shape' => 'CreateProvisioningArtifactOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'LimitExceededException', ], ], ], 'CreateServiceAction' => [ 'name' => 'CreateServiceAction', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CreateServiceActionInput', ], 'output' => [ 'shape' => 'CreateServiceActionOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'LimitExceededException', ], ], ], 'CreateTagOption' => [ 'name' => 'CreateTagOption', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'CreateTagOptionInput', ], 'output' => [ 'shape' => 'CreateTagOptionOutput', ], 'errors' => [ [ 'shape' => 'TagOptionNotMigratedException', ], [ 'shape' => 'DuplicateResourceException', ], [ 'shape' => 'LimitExceededException', ], ], ], 'DeleteConstraint' => [ 'name' => 'DeleteConstraint', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeleteConstraintInput', ], 'output' => [ 'shape' => 'DeleteConstraintOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'DeletePortfolio' => [ 'name' => 'DeletePortfolio', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeletePortfolioInput', ], 'output' => [ 'shape' => 'DeletePortfolioOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'ResourceInUseException', ], [ 'shape' => 'TagOptionNotMigratedException', ], ], ], 'DeletePortfolioShare' => [ 'name' => 'DeletePortfolioShare', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeletePortfolioShareInput', ], 'output' => [ 'shape' => 'DeletePortfolioShareOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'OperationNotSupportedException', ], [ 'shape' => 'InvalidStateException', ], ], ], 'DeleteProduct' => [ 'name' => 'DeleteProduct', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeleteProductInput', ], 'output' => [ 'shape' => 'DeleteProductOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ResourceInUseException', ], [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'TagOptionNotMigratedException', ], ], ], 'DeleteProvisionedProductPlan' => [ 'name' => 'DeleteProvisionedProductPlan', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeleteProvisionedProductPlanInput', ], 'output' => [ 'shape' => 'DeleteProvisionedProductPlanOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DeleteProvisioningArtifact' => [ 'name' => 'DeleteProvisioningArtifact', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeleteProvisioningArtifactInput', ], 'output' => [ 'shape' => 'DeleteProvisioningArtifactOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ResourceInUseException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'DeleteServiceAction' => [ 'name' => 'DeleteServiceAction', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeleteServiceActionInput', ], 'output' => [ 'shape' => 'DeleteServiceActionOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'ResourceInUseException', ], ], ], 'DeleteTagOption' => [ 'name' => 'DeleteTagOption', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DeleteTagOptionInput', ], 'output' => [ 'shape' => 'DeleteTagOptionOutput', ], 'errors' => [ [ 'shape' => 'TagOptionNotMigratedException', ], [ 'shape' => 'ResourceInUseException', ], [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DescribeConstraint' => [ 'name' => 'DescribeConstraint', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeConstraintInput', ], 'output' => [ 'shape' => 'DescribeConstraintOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DescribeCopyProductStatus' => [ 'name' => 'DescribeCopyProductStatus', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeCopyProductStatusInput', ], 'output' => [ 'shape' => 'DescribeCopyProductStatusOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DescribePortfolio' => [ 'name' => 'DescribePortfolio', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribePortfolioInput', ], 'output' => [ 'shape' => 'DescribePortfolioOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DescribePortfolioShareStatus' => [ 'name' => 'DescribePortfolioShareStatus', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribePortfolioShareStatusInput', ], 'output' => [ 'shape' => 'DescribePortfolioShareStatusOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'OperationNotSupportedException', ], ], ], 'DescribePortfolioShares' => [ 'name' => 'DescribePortfolioShares', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribePortfolioSharesInput', ], 'output' => [ 'shape' => 'DescribePortfolioSharesOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'DescribeProduct' => [ 'name' => 'DescribeProduct', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeProductInput', ], 'output' => [ 'shape' => 'DescribeProductOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'DescribeProductAsAdmin' => [ 'name' => 'DescribeProductAsAdmin', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeProductAsAdminInput', ], 'output' => [ 'shape' => 'DescribeProductAsAdminOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'DescribeProductView' => [ 'name' => 'DescribeProductView', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeProductViewInput', ], 'output' => [ 'shape' => 'DescribeProductViewOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'DescribeProvisionedProduct' => [ 'name' => 'DescribeProvisionedProduct', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeProvisionedProductInput', ], 'output' => [ 'shape' => 'DescribeProvisionedProductOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'DescribeProvisionedProductPlan' => [ 'name' => 'DescribeProvisionedProductPlan', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeProvisionedProductPlanInput', ], 'output' => [ 'shape' => 'DescribeProvisionedProductPlanOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'DescribeProvisioningArtifact' => [ 'name' => 'DescribeProvisioningArtifact', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeProvisioningArtifactInput', ], 'output' => [ 'shape' => 'DescribeProvisioningArtifactOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], [ 'shape' => 'InvalidParametersException', ], ], ], 'DescribeProvisioningParameters' => [ 'name' => 'DescribeProvisioningParameters', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeProvisioningParametersInput', ], 'output' => [ 'shape' => 'DescribeProvisioningParametersOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DescribeRecord' => [ 'name' => 'DescribeRecord', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeRecordInput', ], 'output' => [ 'shape' => 'DescribeRecordOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DescribeServiceAction' => [ 'name' => 'DescribeServiceAction', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeServiceActionInput', ], 'output' => [ 'shape' => 'DescribeServiceActionOutput', ], 'errors' => [ [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DescribeServiceActionExecutionParameters' => [ 'name' => 'DescribeServiceActionExecutionParameters', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeServiceActionExecutionParametersInput', ], 'output' => [ 'shape' => 'DescribeServiceActionExecutionParametersOutput', ], 'errors' => [ [ 'shape' => 'InvalidParametersException', ], [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DescribeTagOption' => [ 'name' => 'DescribeTagOption', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DescribeTagOptionInput', ], 'output' => [ 'shape' => 'DescribeTagOptionOutput', ], 'errors' => [ [ 'shape' => 'TagOptionNotMigratedException', ], [ 'shape' => 'ResourceNotFoundException', ], ], ], 'DisableAWSOrganizationsAccess' => [ 'name' => 'DisableAWSOrganizationsAccess', 'http' => [ 'method' => 'POST', 'requestUri' => '/', ], 'input' => [ 'shape' => 'DisableAWSOrganizationsAccessInput', ], 'output' => [ 'shape' => 'DisableAWSOrganizationsAccessOutput',`9$�IV  `9$�IV                  �